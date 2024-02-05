<template>
    <div>
        <div class="form-group mb-3">
            <router-link :to="{name: 'createAuto'}" class="btn btn-success">Добавить авто</router-link>
        </div>
        <div v-if='statusMessage'>
            <h3>{{ statusMessage }}</h3>
        </div>
        <div class='row' v-else>
            <div class='col-md-7'>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Марка, модель</th>
                            <th>Гос.номер</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="car, index in autolistCollection">
                            <td>
                                    <a v-if="carPhoto[index][0]" v-on:click="showPhoto(car.ID, index)" href='#' class='btn btn-sm openpopup'>{{ car.NAME }}</a>
                                    <span v-else class='btn-sm'>{{ car.NAME }}</span>
                            </td>
                            <td>
                                {{ carNumber[index][0] }}
                            </td>
                            <td>
                                    <router-link :to="{name: 'editAuto', params: {id: car.ID}}" class="btn btn-sm btn-primary me-3">
                                        Изменить
                                    </router-link>

                                    <a href="#"
                                        class="btn btn-sm btn-danger"
                                        v-on:click="deleteEntry(car.ID, index)">
                                        Удалить
                                    </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import autolistService from "../../autolistService";

export default {
    data: function () {
        return {
            autolistCollection: [],
            statusMessage: 'Идёт загрузка...'
        }
    },
    computed: {
        carNumber() {
            return this.autolistCollection.map(e => Object.values(e.PROPERTY_107||{}))
        },
        carPhoto() {
            return this.autolistCollection.map(e => Object.values(e.PROPERTY_109||{}))
        },
    },
    mounted() {
        var app = this;
        autolistService.getAll()
            .then(function (data) {
                app.autolistCollection = data;
                app.statusMessage = ''
            })
            .catch(function (resp) {
                console.log(resp);
                app.statusMessage = 'Ошибка загрузки списка'
                // alert("Could not load autolist");
            });
    },
    methods: {
        showPhoto(id, index) {
            var app = this;
            if (this.autolistCollection[index]['PROPERTY_109']) {
                let fileId = Object.values(this.autolistCollection[index]['PROPERTY_109'])[0]
                autolistService.getPhoto(id, 109, fileId)
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
