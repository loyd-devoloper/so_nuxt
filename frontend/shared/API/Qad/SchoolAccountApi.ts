import type {SchoolCredentialsType} from "#shared/types/Qad/SchoolAccount";
import {useAxiosDefaultStore} from "~/stores/AxiosDefault";

export async function storeSchoolAccount(schoolCredentials: SchoolCredentialsType){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post("/api/qad/create", schoolCredentials);
        return response.data;
    }catch(error:any)
    {
        throw error.response;
    }

}