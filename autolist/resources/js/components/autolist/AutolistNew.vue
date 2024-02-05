<template>
    <div>
        <div class="form-group">
            <router-link to="/" class="btn btn-primary">Назад</router-link>
        </div>
        <div v-if='statusMessage'>
            <h3>{{ statusMessage }}</h3>
        </div>
        <div class="panel panel-default" v-else>
            <div class="panel-heading">Добавить авто</div>
            <div class="panel-body">
                <form @submit.prevent="saveForm()">
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label class="control-label"><span class='red'>*</span>Марка, модель</label>
                            <input type="text" v-model="car.NAME" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label class="control-label">Гос. номер</label>
                            <input type="text" v-model="car.PROPERTY_107"  pattern="[а-яА-Я]\d{3,3}[а-яА-Я]{2,2}\d{2,3}" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label class="control-label">Фото ПТС</label>
                            <input type="file" @change="fileUpload( $event )" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xs-12 form-group">
                            <button class="btn btn-success me-3">Сохранить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </template>

<script>
import autolistService from "../../autolistService";

export default {
    data: function () {
        return {
            car: {
                NAME: '',
                PROPERTY_107: '',
                PROPERTY_109: ''
            },
            statusMessage: ''
        }
    },
    methods: {
        fileUpload( event ) {
            this.car.PROPERTY_109 = event.target.files[0];
        },
        saveForm() {
            var app = this;
            const formData = new FormData();
            for (let i in app.car) {
                formData.append('fields['+i+']', app.car[i])
            }
            autolistService.createForm(formData)
                .then(function (resp) {
                    app.$router.push({path: '/'});
                })
                .catch(function (resp) {
                    console.log(resp);
                    app.statusMessage = 'Ошибка создания авто'
                });
        },

        showPhoto(id, index) {
            var app = this;
            if (this.autolistCollection[index]['PROPERTY_109']) {
                autolistService.getPhoto(id, 109)
                    .then(function (data) {
                        if (data && data.length > 0) {
                            let photoUrl = "https://" + window.authData.DOMAIN + data[0];
                            let popup = window.open(photoUrl.replace('download=y',''), 'photoPopup','height=400,width=500');
                            if (window.focus) {popup.focus()}
                        }
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        app.statusMessage = 'Ошибка загрузки фото'
                        // alert("Could not load autolist");
                    });
            }
        },
        deleteEntry(id, index) {
            if (confirm("Удалить?")) {
                var app = this;
                autolistService.delete(id)
                    // axios.delete('/autolist/api/v1/autolist/' + id)
                    .then(function (resp) {
                        app.autolistCollection.splice(index, 1);
                    })
                    .catch(function (resp) {
                        console.log(resp)
                        app.statusMessage = 'Ошибка удаления'
                    });
            }
        }
    }
}
</script>
