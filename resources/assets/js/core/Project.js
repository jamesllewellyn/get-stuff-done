import Section from './Section';
class Project{
    constructor(data){
        this.id = data.id;
        this.name = data.name;
        this.created_at = data.created_at;
        this.sections = [];
    }
    addSection(section){
        this.sections.push(new Section(section));
    }
}
export default Project;
