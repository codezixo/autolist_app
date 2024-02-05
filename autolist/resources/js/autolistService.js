import http from "./http-common";

class autolistService {

    getAll() {
        return http.get("/autolist" , {params: window.authData})
            .then((json = {}) => json.data)
    }

    getPhoto(id, field, fileId) {
        let requestData = window.authData;
        // requestData['photoId'] = id;
        return http.get(`/photo/${id}/${field}/${fileId}`, {params: requestData})
            .then((json = {}) => json.data)
    }

    get(id) {
        return http.get(`/autolist/${id}`, {params: window.authData})
            .then((json = {}) => json.data)
    }

    create(fields) {
        let data = {
            fields: fields,
            ...window.authData
        }
        return http.post("/autolist", data);
    }

    createForm(formData) {
        for (let i in window.authData) {
            formData.append(i, window.authData[i])
        }
        return http.post("/autolist", formData,  { headers: { 'Content-Type': 'multipart/form-data' } });
    }


    update(id, fields) {
        let data = {
            fields: fields,
            ...window.authData
        }
        return http.put(`/autolist/${id}`, data);
    }

    updateForm(id, formData) {
        for (let i in window.authData) {
            formData.append(i, window.authData[i])
        }
        formData.append('_method', 'put');
        return http.post(`/autolist/${id}`, formData,  { headers: { 'Content-Type': 'multipart/form-data' } });
    }

    delete(id) {
        return http.delete(`/autolist/${id}`, {params: window.authData});
    }

}

export default new autolistService();
