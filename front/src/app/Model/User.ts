import {Item} from "./Item";

export enum UserType {
    INTERESTING_USER = 2,
    IGNORING_USER = 3,
    BOT_OR_BUSINESS = 8,
}

export class User {
    pk: string;
    username: string;
    biography: string;
    full_name: string;
    items: Array<Item>;
    follower_count: number;
    following_count: number;
    media_count: number;
    user_type: UserType;
}
