import {useAxiosDefaultStore} from "~/stores/AxiosDefault";
import type {FirstTimeLoginType} from "#shared/types/School/firstTimeLoginType";



export async function fetchLatestCurriculum(){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().get("/api/school/latest/curriculum");
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

export async function firstTimeLogin(credentails: FirstTimeLoginType){
    try
    {

        const formData = new FormData();
        for (const item in credentails) {

            if (item !== 'program_offered') {

                formData.append(item, credentails[item]);
            }

        }
        formData.append('program_offered', JSON.stringify(credentails.program_offered));
        const response =   await useAxiosDefaultStore().authAxiosInstances().post("/api/school/first-time-login",formData);
        return response.data.success;
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