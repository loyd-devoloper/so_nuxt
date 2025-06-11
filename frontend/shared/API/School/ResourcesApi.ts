/* eslint-disable @typescript-eslint/no-explicit-any */
import { useAxiosDefaultStore } from "~/stores/AxiosDefault";
import type { NewApplicationType, StudentType } from "#shared/types/School/SchoolApplicationType";
import { PaginateAttr } from "~/shared/enums/PaginateAttr";

export async function fetchTemplate(page: Ref, search: string, sortColumn: Ref, direction: Ref) {
    try {
        const response = await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/school/resources/template?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    } catch (error: any) {
        throw error.response.data.errors;
    }

}

export async function fetchMemo(page: Ref, search: string, sortColumn: Ref, direction: Ref) {
    try {
        const response = await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/school/resources/memo?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    } catch (error: any) {
        throw error.response.data.errors;
    }

}

export async function fetchAttachment(page: Ref, search: string, sortColumn: Ref, direction: Ref) {
    try {
        const response = await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/school/resources/attachment?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    } catch (error: any) {
        throw error.response.data.errors;
    }

}

export async function downloadAttachment(attachment_id?: string, file_name?: string) {
    try {
        const response = await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/school/resources/attachment-download/${attachment_id}`,
                {
                    responseType: "blob",
                });
        const mimeType = response.headers['content-type'];
        console.log(mimeType)
        const blob = new Blob([response.data], {
            type: mimeType,
        });
        const url = window.URL.createObjectURL(blob);

        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', file_name || 'example');
        document.body.appendChild(link);
        link.click();

        // Cleanup
        link.remove();
        setTimeout(() => window.URL.revokeObjectURL(url), 100);
        return response.data;
    } catch (error: any) {
        throw error.response.data.errors;
    }

}