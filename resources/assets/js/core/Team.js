import Project from './Project';
class Team{
    constructor(data){
        let self = this;
        this.id = data.id;
        this.name = data.name;
        this.projects = [];
        this.users = [];
        if(data.projects) {
            data.projects.forEach(function (project) {
                self.projects.push(new Project(project));
            });
        }
        if(data.users) {
            self.users = data.users;
        }
    }
}
export default Team;
