<template>
    <div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Customers</h3>
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
                        <nuxt-link to="/datapelanggan" class="btn btn-primary btn-dim"><em class="icon ni ni-back-ios"></em><span>Back to List</span></nuxt-link>
                    </div>
                </div>
                <form @submit.prevent="update" class="gy-3">
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Customer Branch</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <v-select :options="branch" v-model="data.customer_branch" :reduce="branch => branch" label="entityname" @search="fetchBranch" @input="fillBranch" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Customer Classification</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <v-select :options="classification" v-model="data.customer_classification" :reduce="classification => classification" label="classification_name" @search="fetchClassification" @input="fillClassification" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Customer Code</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.customer_code" :disabled="!edit" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Customer Name</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.customer_name" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Customer Phone Number</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.customer_phone1" :disabled="!edit">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Main Address</label>
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
                            <nuxt-link to="/datapelanggan/create" class="btn btn-primary">Add New Data</nuxt-link>
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
            customers: [],
            classification: [],
            branch: []
        };
    },
    methods: {
        parse (data) {
            this.data = data.data;
            if (this.data.employee) {
                this.customers.push(this.data.customer);
            }
        },
        fillBranch (value) {
            this.data.customer_branch = value.entitycode
            // this.data.entityname = value.description
        },
        fillClassification (value) {
            this.data.customer_classification = value.classification_code
            // this.data.entityname = value.description
        },
        // fillMainaddress (value) {
        //     this.data.idk_status_karyawan = value.description
        //     // this.data.entityname = value.description
        // },
        fetchBranch (search = 'ENT02') {
            this.$axios.$get('/api/v1/datapelanggan/getbranch', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.branch = response.data
                })
        },
        fetchClassification (search = '') {
            this.$axios.$get('/api/v1/klasifikasipelanggan/', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.classification = response.data
                })
        },
        // fetchMainaddress (search = '') {
        //     this.$axios.$get('/api/v1/alamatpelanggan/getaddress', { params: { keyword: search, d: new Date().getTime() } })
        //         .then((response) => {
        //             this.mainaddress = response.data
        //         })
        // },
        fetch () {
            this.$axios.$get(`/api/v1/datapelanggan/${this.$route.params.id}`, { params: { d: new Date().getTime() } })
                .then((data) => {
                    this.parse(data);
                });
        },
        update () {
            const data = { ...this.data, d: new Date().getTime() }

            this.$axios.$patch(`/api/v1/datapelanggan/${this.data.id}`, data)
                .then((data) => {
                    this.parse(data);
                    this.edit = false;
                });
        },
        changeActive() {
            this.$axios
                .$post(
                `/api/v1/datapelanggan/${this.data.id}/change_active`,
                {
                    d: new Date().getTime(),
                }
                )
                .then((data) => {
                this.parse(data);
                });
            this.$axios
                .$post(
                `/api/v1/datapelanggan/${this.data.id}/change_delete`,
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
        this.fetchBranch();
        this.fetchClassification();
        // this.fetchMainaddress();
    }
}
</script>

<style scoped>
@import "@/static/flag/css/flag-icon.min.css";
</style>
