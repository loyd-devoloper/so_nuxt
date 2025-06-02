export type CurriculumCredentails = {
    school_year_start?: string
    school_year_end?: string
    is_open_for_so_application?: string
    regional_director?: string
    assistant_regional_director?: string
    open_date?: string
    closing_date?: string
}
interface StrandItem {
    name?: string;
    values?: string[];
}
export type ProgramsType = {
    track?: string,
    track_key?:string,
    strand?:StrandItem[]
}