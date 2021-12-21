import axios from "axios";

const baseDomain = "http://127.0.0.1:8000";
const baseURL   = `${baseDomain}/v1/`;
const api_key = "xF4RVVfw.IhWts9gjAG1F5b1JauZ2vZSNhxWMOjrU";
const instance = axios.create({
    baseURL,
    headers: {
        'Content-Type': 'application/json',
    }
});
// Set the AUTH token for any request
instance.interceptors.request.use(function (config) {
    config.headers.Authorization =  "Api-Key" + " " + api_key;
    return config;
});

export default instance