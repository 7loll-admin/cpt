/*
 * Import local dependencies
 */

import Axios from 'axios';
import { notification } from 'antd';

const apibaseUrl = `${cptwoointParams.restApiUrl}TinySolutions/cptwooint/v1/cptwooint`;

/*
 * Create a Api object with Axios and
 * configure it for the WordPRess Rest Api.
 */
const Api = Axios.create({
    baseURL: apibaseUrl,
    headers: {
        'X-WP-Nonce': cptwoointParams.rest_nonce
    }
});

export const notifications = ( isTrue, text ) => {
    const message = {
        message: text, //response.data.message,
        placement: 'top',
        style: {
            marginTop: '10px',
        },
    }
    if( isTrue ){
        notification.success( message );
    } else {
        notification.error(message );
    }
}

export const getPostMetas = async ( params ) => {
    const result = await Api.get( `/getPostMetas`, { params } );
    return JSON.parse( result.data );
}

export const updateOptins = async ( prams  ) => {
    const response = await Api.post(`/updateOptions`, prams );
    notifications( 200 === response.status && response.data.updated, response.data.message );
    return response;
}

export const getOptions = async () => {
    return await Api.get(`/getOptions`);
}

export const getPostTypes = async () => {
    return await Api.get(`/getPostTypes`);
}

export const clearCache = async () => {
    const response = await Api.post(`/clearCache`);
    notifications( 200 === response.status && response.data.updated, response.data.message );
    return response;
}
