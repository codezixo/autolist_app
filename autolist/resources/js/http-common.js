import axios from "axios";

let base = window.location.origin + '/autolist/api/v1';

export default axios.create({
    baseURL: base,
    headers: {
        "Content-type": "application/json"
    }
});
