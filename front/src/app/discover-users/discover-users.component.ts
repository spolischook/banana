import {Component, Host, OnInit} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {environment} from "../../environments/environment";
import {User, UserType} from "../Model/User";
import * as moment from 'moment';
import {NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {DiscoverUsersModalComponent} from "../discover-users-modal/discover-users-modal.component";
import {
    debounceTime, distinctUntilChanged, switchMap, switchAll
} from 'rxjs/operators';
import {HttpParams} from "@angular/common/http";

@Component({
  selector: 'app-discover-users',
  templateUrl: './discover-users.component.html',
  styleUrls: ['./discover-users.component.scss']
})
export class DiscoverUsersComponent implements OnInit {
  public proceedUserGoal = 500;
  public actualProceedUser = 0;
  public users: Array<User> = [];
  public usersCount;
  private decision;
  constructor(
      private http: HttpClient,
      private modalService: NgbModal,
  ) { }

  ngOnInit() {
    this.getUsers();
    this.updateUsersCount();
    this.updateProceedUserCount();
  }

  public showItemDetails(user: User) {
      const modalRef = this.modalService.open(DiscoverUsersModalComponent);
      modalRef.componentInstance.user = user;
      modalRef.componentInstance.decision.subscribe({
          next: (user: User) => this.updateUser(user)
      })
  }

  public setAsInteresting(user: User) {
      let newUser = new User();
      newUser.pk = user.pk;
      newUser.user_type = UserType.INTERESTING_USER;

      this.updateUser(newUser);
  }

  public setAsNotInteresting(user: User) {
      let newUser = new User();
      newUser.pk = user.pk;
      newUser.user_type = UserType.IGNORING_USER;

      this.updateUser(newUser);
  }

  public updateUser(user: User) {
      this.http.patch(`${environment.apiUrl}/users`, user).subscribe({
          next: () => {
              this.updateUsersCount();
              this.getUsers();
          }
      });
      this.removeUser(user);
      this.actualProceedUser++;
  }

  fromUnixToDate(unixTimeStamp) {
      return moment.unix(unixTimeStamp).format("MM/DD/YYYY");
  }

  removeUser(targetUser: User) {
      for (let i in this.users) {
          let k = +i; // Now compiler and tslint are happy
          if (this.users[i].pk == targetUser.pk) {
              this.users.splice(k, 1);
          }
      }
  }

  updateUsersCount() {
      this.http.get(`${environment.apiUrl}/users/count`).subscribe({
          next: (count: number) => this.usersCount = count
      });
  }

  updateProceedUserCount() {
      let params = new HttpParams();
      params = params.set('from', moment().format("YYYY-MM-DD"));
      params = params.set('to', moment().format("YYYY-MM-DD"));
      params = params.append('discr[]', 'userType');
      this.http.get(
          `${environment.apiUrl}/user-events/count`,
          {
              params: params
          }
      ).subscribe({
          next: (count: number) => this.actualProceedUser = count
      });
  }

  getUsers() {
      this.http
          .get(`${environment.apiUrl}/users`)
          // .pipe(
          //     // debounceTime(300),
          //     switchAll()
          //     // switchMap(a => a)
          // )
          .subscribe((users: Array<User>) => this.users = users);
  }
}
