
import {useAuthStore} from "~/stores/AuthStore";

export default defineNuxtRouteMiddleware(async () => {
    // isAuthenticated() is an example method verifying if a user is authenticated
   const token:string | null = localStorage.getItem('token');

   const authStore = useAuthStore();
   if(token)
   {

      if(Object.keys(authStore.authUser).length === 0)
      {

         try {
            await authStore.userInfo()
            // eslint-disable-next-line @typescript-eslint/no-unused-vars
         }catch(error : unknown){
            // return navigateTo('/qad');
         }
      }

   }else{
      return navigateTo('/qad');
   }
})