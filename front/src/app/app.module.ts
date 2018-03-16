import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';


import { AppComponent } from './app.component';
import {ChartsModule} from "ng2-charts";
import {IconsModule} from "./icons/icons.module";
import {HttpClientModule} from "@angular/common/http";
import {NgbModule} from "@ng-bootstrap/ng-bootstrap";
import { DashboardComponent } from './dashboard/dashboard.component';
import { DashboardMainMenuComponent } from './dashboard-main-menu/dashboard-main-menu.component';
import { AppRoutingModule } from './app-routing.module';
import { DiscoverHashTagComponent } from './discover-hash-tag/discover-hash-tag.component';
import { DiscoverUsersComponent } from './discover-users/discover-users.component';
import { DiscoverUsersModalComponent } from './discover-users-modal/discover-users-modal.component';


@NgModule({
  declarations: [
    AppComponent,
    DashboardComponent,
    DashboardMainMenuComponent,
    DiscoverHashTagComponent,
    DiscoverUsersComponent,
    DiscoverUsersModalComponent
  ],
  imports: [
    BrowserModule,
    NgbModule.forRoot(),
    ChartsModule,
    IconsModule,
    HttpClientModule,
    AppRoutingModule
  ],
  providers: [],
  bootstrap: [AppComponent],
  entryComponents: [
      AppComponent,
      DiscoverUsersModalComponent
  ]
})
export class AppModule { }
