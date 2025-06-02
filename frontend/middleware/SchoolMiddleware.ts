
import {useAuthStore} from "~/stores/AuthStore";

export default defineNuxtRouteMiddleware(async () => {
    // isAuthenticated() is an example method verifying if a user is authenticated
    const token:string | null = localStorage.getItem('token');
    const role:string | null = localStorage.getItem('role');

    const authStore = useAuthStore();
    if(token && (role && role === 'School'))
    {

        if(Object.keys(authStore.authUser).length === 0)
        {

            try {
                await authStore.schoolInfo()
                // eslint-disable-next-line @typescript-eslint/no-unused-vars
            }catch(error : unknown){
                return navigateTo('/');
            }
        }

    }else{
        return navigateTo('/');
    }
})