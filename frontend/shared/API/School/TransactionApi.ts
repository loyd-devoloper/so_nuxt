import {useAxiosDefaultStore} from "~/stores/AxiosDefault";
import type {FirstTimeLoginType} from "#shared/types/School/firstTimeLoginType";
import type {NewApplicationType} from "#shared/types/School/SchoolApplicationType";



export async function fetchActiveCurriculum(){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().get("/api/school/active-curricula");
        return response.data;
    }catch(error:any)
    {
        if(error.response.status === 442)
        {
            throw error.response.data.errors;
        }else{
            throw error.response.data;
        }

    }

}
export async function storeApplication(applicationData: NewApplicationType){
    try
    {
        const formData = new FormData();
        for (const item in applicationData) {

                formData.append(item, applicationData[item]);


        }
        const response =   await useAxiosDefaultStore().authAxiosInstances().post("/api/school/transaction/store",applicationData);
        return response.data;
    }catch(error:any)
    {
        if(error.response.status === 442)
        {
            throw error.response.data.errors;
        }else{
            throw error.response.data;
        }

    }

}