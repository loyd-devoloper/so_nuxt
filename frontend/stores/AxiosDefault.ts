import axios, { type AxiosResponse, type AxiosError } from 'axios'

export const useAxiosDefaultStore = defineStore('axiosDefault', () => {
  const config = useRuntimeConfig()
  const timeout = ref<number>(1000 * 100);

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
 AxiosAuth.interceptors.response.use(
      (response: AxiosResponse): AxiosResponse => {

        return response
      },
      (error: AxiosError | any): Promise<AxiosError> => {
        const status = error.response?.status


        switch (status) {

          case 429:
            showError({
              statusCode: 429,
              statusMessage: error.response?.data?.message
            })
            break
            case 500:
            showError({
              statusCode: 500,
              statusMessage: error.response?.data?.message
            })
            break
          // Add other cases as needed
        }


        return Promise.reject(error)
      }
    )

    return AxiosAuth;
  };

  const guestAxiosInstance = () => {
    const axiosG = axios.create({
      baseURL: config.public.backend,
      timeout: timeout.value,
      withCredentials: true
    });
    axiosG.interceptors.response.use(
      (response: AxiosResponse): AxiosResponse => {

        return response
      },
      (error: AxiosError | any): Promise<AxiosError> => {
        const status = error.response?.status


        switch (status) {

          case 429:
            showError({
              statusCode: 429,
              statusMessage: error.response?.data?.message
            })
            break
          // Add other cases as needed
        }


        return Promise.reject(error)
      }
    )
    return axiosG;
  }

  return { guestAxiosInstance, authAxiosInstances }
})