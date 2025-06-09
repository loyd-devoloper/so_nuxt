import { useAxiosDefaultStore } from "~/stores/AxiosDefault";
import type { FirstTimeLoginType } from "#shared/types/School/firstTimeLoginType";
import type { NewApplicationType, StudentType } from "#shared/types/School/SchoolApplicationType";
import { PaginateAttr } from "~/shared/enums/PaginateAttr";
import type { AnnouncementType } from "~/shared/types/Qad/AnnouncementType";

export async function storeNewAnnouncement(announcementData: AnnouncementType) {
    try {
        const formData = new FormData();
        formData.append('content', announcementData.content || '');
        formData.append('type', announcementData.type || '');
        formData.append('expiration_date', announcementData.expiration_date || '');
        formData.append('file', announcementData.file || '');
        const response = await useAxiosDefaultStore()
            .authAxiosInstances()
            .post(`/api/qad/announcement/store`, formData);
        return response.data.success;
    } catch (error: any) {
        throw error.response.data.errors;
    }

}
export async function fetchAnnouncement(page: Ref, search: string, sortColumn: Ref, direction: Ref) {
    try {
        const response = await useAxiosDefaultStore()
            .authAxiosInstances()
            .get(`/api/qad/announcement?q=${search}&limit=${PaginateAttr.LIMIT}&page=${page.value}&sort=${sortColumn.value}&d=${direction.value}`);
        return response.data;
    } catch (error: any) {
        throw error.response.data.errors;
    }

}

export async function editAnnouncement(announcement_id: string | number) {
    try {
        const response = await useAxiosDefaultStore().authAxiosInstances().get(`/api/qad/announcement/edit/${announcement_id}`);
        return response.data;
    } catch (error: any) {
        throw error.response;
    }

}
export async function updateAnnouncement(announcementData: AnnouncementType, announcement_id: string | number) {
    try {
        const formData = new FormData();
        formData.append('content', announcementData.content || '');
        formData.append('type', announcementData.type || '');
        formData.append('expiration_date', announcementData.expiration_date || '');
        formData.append('file', announcementData.file || '');
        const response = await useAxiosDefaultStore().authAxiosInstances().post(`/api/qad/announcement/update/${announcement_id}`, formData);
        return response.data.success;
    } catch (error: any) {
        throw error.response;
    }

}
export async function previewAttachment(announcement_id: string | number) {
    try {
        const response = await useAxiosDefaultStore()
            .authAxiosInstances().get(
                `/api/qad/announcement/viewFile/${announcement_id}`,
                {
                    responseType: "blob",
                }
            );

        const mimeType = response.headers['content-type'];

        const blob = new Blob([response.data], {
            type: mimeType,
        });
        return window.URL.createObjectURL(blob);


    } catch (error: any) {
        throw error.response;
    }
}