
import { registerBlockType } from '@wordpress/blocks'
import {useBlockProps} from '@wordpress/block-editor'
import {useSelect, useDispatch} from '@wordpress/data'
import {useState} from '@wordpress/element'
import apiFetch from '@wordpress/api-fetch';


import './index.scss'

registerBlockType('fsdgpb/google-place', {
  edit({attributes, setAttributes, context}){

    const blockProps = useBlockProps();

    const [googleMapsUrl, setGoogleMapsUrl] = useState('');


    const handleGoogleUrlSubmit = async (e) => {
      // Hit custom REST endpoint to handle google API request
      
      // Expected return of object:
      e.preventDefault()

      const placeString = googleMapsUrl.split('/place/')[1].split('/@')[0]
      const lat = googleMapsUrl.split('@')[1].split(',')[0]
      const lng = googleMapsUrl.split('@')[1].split(',')[1]

      const res = await apiFetch({path: 'fsdhh/v1/fetch-google-data', method: "POST", data: {
        placeString: placeString,
        lat: lat,
        lng: lng
      }});

      const json = JSON.parse(res)

      console.log(json)


      // setAttributes({lat: x, long: x, rating: x})

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
            <label htmlFor="googleLink">Link to location on Google maps:</label>
            <input 
              id="googleLink"
              type="text" 
              value={googleMapsUrl}
              onChange={(e) => setGoogleMapsUrl(e.target.value) }
            />
            <button type="submit">Fetch</button>
          </form>
        </div>
        
      </div>
    )
  },
  save(){
    return null
  }
})