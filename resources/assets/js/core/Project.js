class Project{
    constructor($data){
        this.id = $data.id;
        this.name = $data.name;
        this.created_at = $data.created_at;
        this.sections = $data.sections;
    }
}
export default Project;
