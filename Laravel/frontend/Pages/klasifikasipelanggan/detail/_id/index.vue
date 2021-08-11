<template>
    <div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Klasifikasi Pelanggan</h3>
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
                        <nuxt-link to="/klasifikasipelanggan" class="btn btn-primary btn-dim"><em class="icon ni ni-back-ios"></em><span>Back to List</span></nuxt-link>
                    </div>
                </div>
                <form @submit.prevent="update" class="gy-3">
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Kode Klasifikasi Pelanggan</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.classification_code" :disabled="!edit" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Nama Klasifikasi Pelanggan</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.classification_name" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Akun Akrual Piutang</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <v-select :options="akrual" v-model="data.account_akrual_piutang" :reduce="akrual => akrual" label="accountname" @search="fetchAkrual" @input="fillAkrual" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Akun Piutang</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <v-select :options="piutang" v-model="data.account_piutang" :reduce="piutang => piutang" label="accountname" @search="fetchPiutang" @input="fillPiutang" :disabled="!edit"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Akun Uang Muka</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <v-select :options="uangmuka" v-model="data.account_downpayment" :reduce="uangmuka => uangmuka" label="accountname" @search="fetchUangmuka" @input="fillUangmuka" :disabled="!edit"/>
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
                            <nuxt-link to="/klasifikasipelanggan/create" class="btn btn-primary">Add New Data</nuxt-link>
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
            custclassifications: [],
            akrual: [],
            piutang: [],
            uangmuka: []
        };
    },
    methods: {
        parse (data) {
            this.data = data.data;
            if (this.data.employee) {
                this.custclassifications.push(this.data.custclass);
            }
        },
        fillAkrual (value) {
            this.data.account_akrual_piutang = value.accountnumber
            // this.data.entityname = value.description
        },
        fillPiutang (value) {
            this.data.account_piutang = value.accountnumber
            // this.data.entityname = value.description
        },
        fillUangmuka (value) {
            this.data.account_downpayment = value.accountnumber
            // this.data.entityname = value.description
        },
        fetchAkrual (search = '') {
            this.$axios.$get('/api/v1/klasifikasipelanggan/getaccount', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.akrual = response.data
                })
        },
        fetchPiutang (search = '1102') {
            this.$axios.$get('/api/v1/klasifikasipelanggan/getaccount', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.piutang = response.data
                })
        },
        fetchUangmuka (search = '1105') {
            this.$axios.$get('/api/v1/klasifikasipelanggan/getaccount', { params: { keyword: search, d: new Date().getTime() } })
                .then((response) => {
                    this.uangmuka = response.data
                })
        },
        fetch () {
            this.$axios.$get(`/api/v1/klasifikasipelanggan/${this.$route.params.id}`, { params: { d: new Date().getTime() } })
                .then((data) => {
                    this.parse(data);
                });
        },
        update () {
            const data = { ...this.data, d: new Date().getTime() }

            this.$axios.$patch(`/api/v1/klasifikasipelanggan/${this.data.id}`, data)
                .then((data) => {
                    this.parse(data);
                    this.edit = false;
                });
        },
        changeActive() {
            this.$axios
                .$post(
                `/api/v1/klasifikasipelanggan/${this.data.id}/change_active`,
                {
                    d: new Date().getTime(),
                }
                )
                .then((data) => {
                this.parse(data);
                });
            this.$axios
                .$post(
                `/api/v1/klasifikasipelanggan/${this.data.id}/change_delete`,
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
        this.fetchAkrual();
        this.fetchPiutang();
        this.fetchUangmuka();
    }
}
</script>

<style scoped>
@import "@/static/flag/css/flag-icon.min.css";
</style>
