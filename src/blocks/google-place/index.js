
import { registerBlockType } from '@wordpress/blocks'
import {useBlockProps} from '@wordpress/block-editor'
import {useState} from '@wordpress/element'
import apiFetch from '@wordpress/api-fetch';


import './index.scss'

registerBlockType('fsdgpb/google-place', {
  edit({attributes, setAttributes, context}){

    const blockProps = useBlockProps();

    const [googleMapsUrl, setGoogleMapsUrl] = useState('');


    const handleGoogleUrlSubmit = async (e) => {

      e.preventDefault()

      // Break apart our url to grab the placeId and the lat and lng
      const placeId = googleMapsUrl.split('/place/')[1].split('/@')[0]
      const lat = googleMapsUrl.split('@')[1].split(',')[0]
      const lng = googleMapsUrl.split('@')[1].split(',')[1]

      // Hit custom REST endpoint to handle google API request
      const res = await apiFetch({path: 'fsdgpb/v1/fetch-google-data', method: "POST", data: {
        placeId: placeId,
        lat: lat,
        lng: lng
      }});

      // We'll expect stringified JSON back
      const json = JSON.parse(res)
      console.log(json)
      const place = json.candidates[0]

     

      setAttributes({
        lat: place.geometry.location.lat, 
        lng: place.geometry.location.lng, 
        rating: place.rating
      })

      // image also returned, but not immediatly stored

      // setState(...image...)

      // seperate handler will allow manual saving of image if desired
    }

    return (
      <div {...blockProps}>
        <strong>Place Data</strong>
        <hr />
        <div className='mt-5'>
          {/* <button onClick={handleGoogleUrlSubmit}>Test Endpoint</button> */}
          <form onSubmit={handleGoogleUrlSubmit}>
            <div>
              <label htmlFor="googleLink">Link to location on Google maps:</label>
              <input 
                id="googleLink"
                type="text" 
                value={googleMapsUrl}
                onChange={(e) => setGoogleMapsUrl(e.target.value) }
              />
            </div>
            <button type="submit">Fetch</button>
          </form>
        </div>
        <div>
          <table>
            <tr>
              <td>Latitude:</td>
              <td>{attributes.lat}</td>
            </tr>
            <tr>
              <td>Longitude:</td>
              <td>{attributes.lng}</td>
            </tr>
            <tr>
              <td>Rating:</td>
              <td>{attributes.rating}</td>
            </tr>
            <tr>
              <td>Price Level:</td>
              <td>{attributes.price}</td>
            </tr>
            <tr>
              <td>Address:</td>
              <td>{attributes.address}</td>
            </tr>
          </table>
        </div>
        
      </div>
    )
  },
  save(){
    return null
  }
})