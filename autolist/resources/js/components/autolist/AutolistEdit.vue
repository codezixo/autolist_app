<template>
    <div>
        <div class="form-group">
            <router-link to="/" class="btn btn-primary">Назад</router-link>
        </div>
        <div v-if='statusMessage'>
            <h3>{{ statusMessage }}</h3>
        </div>
        <div class="panel panel-default" v-else>
            <div class="panel-heading">Изменить данные авто</div>
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
                            <input type="text" v-model="car.PROPERTY_107" pattern="[а-яА-Я]\d{3,3}[а-яА-Я]{2,2}\d{2,3}" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label class="control-label">Фото ПТС</label>
                            <div v-if="photoLoaded" class="mt-2 mb-2">
                                <a href="#" @click.prevent="showPhoto">Открыть</a>
                            </div>
                            <input type="file" @change="fileUpload( $event )" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group mt-3">
                            <button class="btn btn-success">Сохранить</button>
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
            carId: null,
            photoLoaded: false,
            newPhoto: null,
            car: {
                NAME: '',
                PROPERTY_107: '',
                PROPERTY_109: ''
            },
            statusMessage: 'Загрузка...'
        }
    },
    mounted() {
        let app = this;
        let id = app.$route.params.id;
        app.carId = id;
        autolistService.get(app.carId)
            .then(function (data) {
                if (data.length > 0) {
                    app.car = data[0];
                    app.car.PROPERTY_107 = app.getMultiPropValue('PROPERTY_107', app.car)
                    app.car.PROPERTY_109 = app.getMultiPropValue('PROPERTY_109', app.car)
                    if (app.car.PROPERTY_109) app.photoLoaded = true;
                    app.statusMessage = ''
                } else {
                    console.log(resp);
                    app.statusMessage = 'Авто не найдено'
                }
            })
            .catch(function (resp) {
                console.log(resp);
                app.statusMessage = 'Ошибка загрузки авто'
            });
    },
    methods: {
        getMultiPropValue(propId, car) {
            let carProp = ''
            let carPropArr = Object.values(car[propId] || {})
            if (carPropArr.length > 0)  carProp = carPropArr[0]
            return carProp
        },
        fileUpload( event ) {
            this.newPhoto = event.target.files[0];
        },
        saveForm() {
            var app = this;
            const formData = new FormData();
            for (let i in app.car) {
                formData.append('fields['+i+']', app.car[i])
            }
            if (app.newPhoto) {
                formData.append('fields[PROPERTY_109]', app.newPhoto)
            }
            autolistService.updateForm(app.carId, formData)
                .then(function (resp) {
                    app.$router.push({path: '/'});
                })
                .catch(function (resp) {
                    console.log(resp);
                    app.statusMessage = 'Ошибка сохранения авто'
                });
        },

        showPhoto() {
            var app = this;
            autolistService.getPhoto(app.carId, 109, app.car.PROPERTY_109)
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
        },

        deleteEntry(id) {
            if (confirm("Удалить?")) {
                var app = this;
                autolistService.delete(id)
                    .then(function (resp) {
                        app.$router.push({path: '/'});
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
