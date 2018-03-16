import {Media} from "./Media";

export enum ItemMediaType {
    PHOTO = 1,
    VIDEO = 2,
    ALBUM = 8,
}

export class Item {
    id: string;
    pk: string;
    media_type: number;
    taken_at: number;
    image_versions2: Array<Media>;
    video_versions: Array<Media>;
    caption: string;
    carousel_media: Array<Item>
}
