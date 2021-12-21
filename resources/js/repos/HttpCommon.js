import instance from './repository';

export default {
    get(resource){
        return instance.get(`${resource}`);
    },
    post(resource, data){
        return instance.post(`${resource}`, data);
    }
}