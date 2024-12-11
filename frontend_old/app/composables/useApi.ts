import axios from 'axios'
import { getData, setData } from 'nuxt-storage/local-storage';

export const useApi = () => {
    const baseURL = 'http://localhost:8080/index.php/gender_analytics/api/v1'

    const api = axios.create({ baseURL })
    
    api.interceptors.request.use(
        config => {
            // let appData = localStorage.getItem('userToken');
            // appData = JSON.parse(appData);
            // const token = appData.value;
            // if (token) {
            //     config.headers['Authorization'] = 'Bearer ' + token;
            // }
            return config;
        },
        error => {
            Promise.reject(error)
        }
    );

    return api
}
