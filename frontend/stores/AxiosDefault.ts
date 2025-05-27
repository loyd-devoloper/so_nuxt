import axios from "axios"

export const useAxiosDefaultStore = defineStore('axiosDefault', () => {
   const config = useRuntimeConfig()
    const timeout = ref<number>( 1000 * 20);

    const authAxiosInstances = () => {
        const AxiosAuth = axios.create({
            baseURL: config.public.backend,
            timeout: timeout.value,
            withCredentials: true
        });

        AxiosAuth.interceptors.request.use(
            (config) => {
                const token = localStorage.getItem('token'); // Retrieve the token from local storage
                if (token) {
                    config.headers['Authorization'] = `Bearer ${token}`; // Set the Authorization header
                }
                return config;
            },
            (error) => {
                return Promise.reject(error);
            }
        );

        return AxiosAuth;
    };

    const guestAxiosInstance = () => {
        return axios.create({
            baseURL: config.public.backend,
            timeout: timeout.value,
            withCredentials: true
        });
    }

    return { guestAxiosInstance,authAxiosInstances }
})