export type NewApplicationType =  {

    attestation_file?: File | null;
    form9_file?: File | null;
    students_file?: File | null;
    graduation_date?: Date | null;
    curriculum_id?: string;
    applied_track?:string;
    applied_strand?:string;
    applied_specialization?: string[] | number[]

};