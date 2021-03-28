import React from 'react';
import { Alert } from 'react-native';

import axios from 'axios';
import * as UserStorage from '../src/UserStorage';
import ServerRoutes from '../src/ServerRoutes';


function getUserInstance(user_data, token)
{
    const AxiosInstance = axios.create({
        baseURL: user_data.server.url,
        timeout: 20000,
        headers: {
            'Authorization': 'Bearer ' + token,
            'Cache-Control': 'no-cache',
            'Pragma': 'no-cache',
            'Expires': '0',
        }
    });
    

    AxiosInstance.interceptors.response.use((response) =>{
        return response;
    }, (error) => {
        const originalRequest = error.config;
        if (!error.response) 
        {
            return Promise.reject('Network Error')
        }
        else if ((error.response.status === 498) && !originalRequest._retry) 
        {
            originalRequest._retry = true;

            return axios.post(user_data.server.url +  ServerRoutes.api + "/auth/login", null, {
                params: {
                    phone: user_data.server.id,
                    password: user_data.server.pwd
                }
            }).then((response) => {
                const data = response.data;
                token = data.token;
                UserStorage.saveToken(token);

                AxiosInstance.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                originalRequest.headers['Authorization'] = 'Bearer ' + token;
                return axios(originalRequest);

            }).catch((error) => { 

                return error.response;
            });

        } else 
        {
            return error.response
        }

    });

    return AxiosInstance;
}

async function executeAxiosCmd(resolve, reject)
{
    UserStorage.retrieve().then((user_data) => {
        UserStorage.getToken().then((token) => {

            if(token==null)
            {
                axios.post(user_data.server.url +  ServerRoutes.api + "/auth/login", null, {
                    params: {
                        phone: user_data.server.id,
                        password: user_data.server.pwd
                    }
                }).then((response) => {
                    const data = response.data;

                    token = data.token;
                    UserStorage.saveToken(token);

                    const AxiosInstance = getUserInstance(user_data, token);
                    resolve(AxiosInstance);
                }).catch((error)=> {

                    reject(error);
                });

                return;
            }

            const AxiosInstance = getUserInstance(user_data, token);
            resolve(AxiosInstance);
        });
    });
}

function ArgumentsToArray(args) {
    return [].slice.apply(args);
}

export default {
    get: async function () {
        return (await (new Promise((resolve, reject)=> executeAxiosCmd(resolve, reject)))).get.apply(this, arguments);
    },
    post: async function () {
        return (await (new Promise((resolve, reject)=> executeAxiosCmd(resolve, reject)))).post.apply(this, arguments);
    },
    put: async function ()  {
        return (await (new Promise((resolve, reject)=> executeAxiosCmd(resolve, reject)))).put.apply(this, arguments);
    },
    delete: async function ()  {
        return (await (new Promise((resolve, reject)=> executeAxiosCmd(resolve, reject)))).delete.apply(this, arguments);
    }
};