import {AccountRoleEnum} from "#shared/enums/AccountRoleEnum";
import {useAuthStore} from "~/stores/AuthStore";

export default defineNuxtRouteMiddleware(async () => {
    // isAuthenticated() is an example method verifying if a user is authenticated
   const token:string | null = localStorage.getItem('token');
   const role:string | null = localStorage.getItem('role');
   const authStore = useAuthStore();
   if((AccountRoleEnum.QAD === role || AccountRoleEnum.TEAHUB === role) && token )
   {
      if(Object.keys(authStore.authUser).length === 0)
      {
         try {
            await authStore.userInfo(AccountRoleEnum.QAD)

            // eslint-disable-next-line @typescript-eslint/no-unused-vars
         }catch(error : unknown){
            return navigateTo('/qad');
         }
      }
   }else{
      return navigateTo('/qad');
   }
})