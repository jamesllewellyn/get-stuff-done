import Section from './Section';
class Project{
    constructor($data){
        let self = this;
        this.id = $data.id;
        this.name = $data.name;
        this.created_at = $data.created_at;
        this.sections = [];
        if($data.sections) {
            $data.sections.forEach(function (section) {
                self.addSection(section);
            });
        }
    }
    addSection(section){
        this.sections.push(new Section(section));
    }
}
export default Project;
