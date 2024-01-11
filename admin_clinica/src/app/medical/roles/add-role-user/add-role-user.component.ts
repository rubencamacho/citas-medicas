import { Component } from '@angular/core';
import { DataService } from 'src/app/shared/data/data.service';

@Component({
  selector: 'app-add-role-user',
  templateUrl: './add-role-user.component.html',
  styleUrls: ['./add-role-user.component.scss']
})
export class AddRoleUserComponent {
    sidebar:any = [];
    name = '';
    permission:any = [];


    constructor(public DataService: DataService) { 

    }

    ngOnInit(): void {
      this.sidebar = this.DataService.sideBar[0].menu;
    }

    addPermission(submenu:any){
      if(submenu.permision){
        const INDEX = this.permission.findIndex((item:any) => item == submenu.permision);
        if(INDEX != -1){
          this.permission.splice(INDEX, 1);
        }else{
          this.permission.push(submenu.permision);
        }
        console.log(this.permission);
      }
    }

}
