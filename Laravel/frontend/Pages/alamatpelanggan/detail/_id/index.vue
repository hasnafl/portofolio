<template>
    <div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Alamat Pelanggan</h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-inner" v-if="data">
                <div class="card-title-group align-start gx-3 mb-3">
                    <div class="card-title">
                        <h6 class="title">{{ edit ? 'Editing' : 'Viewing' }} #{{ $route.params.id }}</h6>
                    </div>
                    <div class="card-tools">
                        <nuxt-link to="/alamatpelanggan" class="btn btn-primary btn-dim"><em class="icon ni ni-back-ios"></em><span>Back to List</span></nuxt-link>
                    </div>
                </div>
                <form @submit.prevent="update" class="gy-3">
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Kode Pelanggan</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <v-select disabled :options="pelanggan" v-model="data.address_customercode" :reduce="pelanggan => pelanggan" label="customer_name" @search="fetchPelanggan" @input="fillPelanggan"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Label Alamat</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.address_label" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Detail Alamat</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <textarea class="form-control" v-model="data.address_detail" :disabled="!edit"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Keterangan</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.description" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0">Status</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <Label :active="data.is_active"/>
                            </div>
                        </div>
                    </div>
                    <div class="justify-content-end d-flex" v-if="edit">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

                <div v-if="!edit" class="mt-5">
                    <strong>
                        Metadata Information
                    </strong>

                    <hr>
                    <Metadata :data="data"/>

                    <div class="g-3 d-flex justify-content-between">
                        <div>
                            <change-active
                                :active="data.is_active"
                                :callback="changeActive"
                            />
                        </div>
                        <div>
                            <button type="button" class="btn btn-light" @click="edit = true">Edit</button>
                            <nuxt-link to="/alamatpelanggan/create" class="btn btn-primary">Add New Data</nuxt-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '@/components/Editor';
import Label from '@/components/Label';
import ChangeActive from "@/components/ChangeActive";

export default {
    components: {
        Editor,
        Label,
        ChangeActive,
    },
    data () {
        return {
            data: null,
            edit: false,
            addresses: [],
            pelanggan: []
        };
    },
    methods: {
        parse (data) {
            this.data = data.data;
            if (this.data.employee) {
                this.addresses.push(this.data.address);
            }
        },
        fillPelanggan (value) {
            this.data.address_customercode = value.customer_code
        },
        fetchPelanggan (search = '') {
            this.$axios.$get('/api/v1/datapelanggan/', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.pelanggan = response.data
                })
        },
        fetch () {
            this.$axios.$get(`/api/v1/alamatpelanggan/${this.$route.params.id}`, { params: { d: new Date().getTime() } })
                .then((data) => {
                    this.parse(data);
                });
        },
        update () {
            const data = { ...this.data, d: new Date().getTime() }

            this.$axios.$patch(`/api/v1/alamatpelanggan/${this.data.id}`, data)
                .then((data) => {
                    this.parse(data);
                    this.edit = false;
                });
        },
        changeActive() {
            this.$axios
                .$post(
                `/api/v1/alamatpelanggan/${this.data.id}/change_active`,
                {
                    d: new Date().getTime(),
                }
                )
                .then((data) => {
                this.parse(data);
                });
            this.$axios
                .$post(
                `/api/v1/alamatpelanggan/${this.data.id}/change_delete`,
                {
                    d: new Date().getTime(),
                }
                )
                .then((data) => {
                this.parse(data);
                });
        }
    },
    created () {
        this.fetch();
        this.fetchPelanggan();
    }
}
</script>

<style scoped>
@import "@/static/flag/css/flag-icon.min.css";
</style>
