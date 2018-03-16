import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {RouterModule, Routes} from "@angular/router";
import {DashboardComponent} from "./dashboard/dashboard.component";
import {DiscoverHashTagComponent} from "./discover-hash-tag/discover-hash-tag.component";
import {DiscoverUsersComponent} from "./discover-users/discover-users.component";

const appRoutes: Routes = [
    {path: '', component: DashboardComponent},
    {path: 'hash-tag', component: DiscoverHashTagComponent},
    {path: 'users', component: DiscoverUsersComponent},
];

@NgModule({
  imports: [
    CommonModule,
    RouterModule.forRoot(appRoutes)
  ],
  exports: [
    RouterModule
  ],
})
export class AppRoutingModule { }
