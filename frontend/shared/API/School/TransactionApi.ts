import {useAxiosDefaultStore} from "~/stores/AxiosDefault";
import type {FirstTimeLoginType} from "#shared/types/School/firstTimeLoginType";
import type {NewApplicationType} from "#shared/types/School/SchoolApplicationType";
import { PaginateAttr } from "~/shared/enums/PaginateAttr";



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
export async function fetchSchoolApplication(page: Ref,search: string,sortColumn: Ref,direction: Ref){
    try
    {
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/school/transaction?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}
export async function fetchSoStudents(page: Ref,search: string,sortColumn: Ref,direction: Ref,application_id: any){
    try
    {
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/school/transaction/students/${application_id}?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}
export async function storeApplication(applicationData: NewApplicationType){
    try
    {
        console.log(applicationData)
        const formData = new FormData();
        for (const item in applicationData) {

             if(item !== 'applied_specialization')    formData.append(item, applicationData[item]);
                

        }
        formData.append('applied_specialization',JSON.stringify(applicationData.applied_specialization))
        const response =   await useAxiosDefaultStore().authAxiosInstances().post("/api/school/transaction/store",formData);
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