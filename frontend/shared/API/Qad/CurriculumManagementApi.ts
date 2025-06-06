
import {useAxiosDefaultStore} from "~/stores/AxiosDefault";

import {PaginateAttr} from "#shared/enums/PaginateAttr";
import type {Ref} from "vue";
import type {SchoolCredentialsType} from "#shared/types/Qad/SchoolAccount";
import type {CurriculumCredentails, ProgramsType} from "#shared/types/Qad/CurriculumType";

export async function storeCurriculum(curriculumCredentials: CurriculumCredentails){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post("/api/qad/curriculum/store", curriculumCredentials);
        return response.data.success;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}

export async function fetchCurricula(page: Ref,search: string,sortColumn: Ref,direction: Ref){
    try
    {
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/qad/curriculum?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}

export async function editCurriculum(curriculum_id: string | number){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().get(`/api/qad/curriculum/edit/${curriculum_id}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response;
    }

}

export async function updateCurriculum(curriculum_id: string | number,curriculumCredentials: CurriculumCredentails){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post(`/api/qad/curriculum/update/${curriculum_id}`,curriculumCredentials);
        return response.data.success;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}

export async function storeProgram(programData: ProgramsType,curriculum_id: string | number | any){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post(`/api/qad/curriculum/store-program/${curriculum_id}`,programData);
        return response.data.success;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}
export async function fetchPrograms(page: Ref,search: string,sortColumn: Ref,direction: Ref,curriculum_id: string | number){
    try
    {
        const response =   await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/qad/curriculum/programs/${curriculum_id}?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}
export async function editProgram(curriculum_id?: string | number,program_id?: string | number){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().get(`/api/qad/curriculum/program/edit/${curriculum_id}/${program_id}`);
        return response.data;
    }catch(error:any)
    {
        throw error.response;
    }

}
export async function updateProgram(programData: ProgramsType,program_id?: string | number){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().post(`/api/qad/curriculum/program/update/${program_id}`,programData);
        return response.data.success;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}
export async function deleteProgram(program_id?: string | number){
    try
    {
        const response =   await useAxiosDefaultStore().authAxiosInstances().delete(`/api/qad/curriculum/program/delete/${program_id}`);
        return response.data.success;
    }catch(error:any)
    {
        throw error.response.data.errors;
    }

}