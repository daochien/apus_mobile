<template>
    <div class="row justify-content-center">
        <div class="col-md-12 text-right" style="margin-bottom: 10px;">
            <a href="/admin/app-customers" class="btn btn-responsive btn-warning">Hủy bỏ</a>
            <button @click.prevent="edit(form.id)" v-if="editModel" type="submit" class="btn btn-responsive btn-info">Cập nhật</button>
            <button @click.prevent="saveForm()" v-else class="btn btn-responsive btn-primary">Tạo mới</button>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="input">Tên khách hàng(*)</label>
                            <input v-model="form.customer_name" :class="['form-control', { 'is-invalid': form.errors.has('customer_name') }]">
                            <has-error :form="form" field="customer_name"></has-error>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="input">Số điện thoại(*)</label>
                            <input v-model="form.customer_phone" :class="['form-control', { 'is-invalid': form.errors.has('customer_phone') }]">
                            <has-error :form="form" field="customer_phone"></has-error>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="input">Email</label>
                            <input v-model="form.customer_email" :class="['form-control', { 'is-invalid': form.errors.has('customer_email') }]">
                            <has-error :form="form" field="customer_email"></has-error>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="input">Packages(*)</label>
                            <select @change="loadConfigs()" v-model="form.package_id" :class="['form-control', { 'is-invalid': form.errors.has('package_id') }]">
                                <option value="">--Chọn--</option>
                                <option v-for="(item, index) in packages" :value="item.id" :key="index">{{ item.name }}</option>
                            </select>
                            <has-error :form="form" field="package_id"></has-error>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="input" v-if="!editModel">Ngày ngày hết hạn(*)</label>
                            <datetime
                                :input-class="['form-control', { 'is-invalid': form.errors.has('expired') }]"
                                type="datetime"
                                v-model="form.expired"
                                format="yyyy-MM-dd HH:mm:ss"
                                value-zone="Asia/Ho_Chi_Minh"
                            ></datetime>

                            <has-error :form="form" field="expired"></has-error>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input">Trạng thái</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="form.status" id="status1" value="1">
                                <label class="form-check-label" for="status1">Activce</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="form.status" id="status2" value="0">
                                <label class="form-check-label" for="status2">InActive</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card-body" style="background: #fff;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="border-top-0" scope="col"></th>
                        <th class="border-top-0" scope="col">Key</th>
                        <th class="border-top-0" scope="col">Type</th>
                        <th class="border-top-0" scope="col">Can Edit</th>
                        <th class="border-top-0" scope="col">Old Value</th>
                        <th class="border-top-0" scope="col">New Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template  v-for="(config, index) in configs" >
                        <tr
                            style="font-weight:bold;"
                            class="clickable"
                            data-toggle="collapse"
                            :key="index"
                            :id="`row${index}`" :data-target="`.row${index}`">
                            <td>
                                <template v-if="config.is_group == 1">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </template>
                            </td>
                            <td>
                                {{ config.key }}
                            </td>
                            <td>{{ config.type ? config.type: '-' }}</td>
                            <td>
                                {{ config.is_edit ? 'Yes': 'No' }}
                            </td>
                            <td>
                                <template v-if="config.type === 'file'">
                                    <img width="50" :src="config.value" alt="">
                                </template>
                                <template v-else>
                                    {{ config.value ? config.value: '-' }}
                                </template>
                            </td>

                            <td>
                                <template v-if="config.is_edit">
                                    <template v-if="config.type === 'checkbox' || config.type === 'radio'">
                                        <input-tag placeholder="Enter..." v-model="config.new_value" :limit="10"></input-tag>
                                    </template>
                                    <template v-else-if="config.type === 'file'">
                                        <div class="form-group">
                                            <input type="file" :class="['form-control']" @change="onSelectConfigItemFile($event, config)">
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="form-group">
                                            <input v-model="config.new_value" :class="['form-control']">
                                        </div>
                                    </template>
                                </template>

                            </td>

                        </tr>
                        <template v-if="config.items.length > 0 && config.is_group == 1" v-for="(child, i) in config.items" >
                            <tr :class="`collapse row${index}`" :key="`child_${index}_${i}`">
                                <td>
                                    -
                                </td>
                                <td>{{ child.key }}</td>
                                <td>{{ child.type }}</td>
                                <td>
                                    {{ child.is_edit ? 'Yes': 'No' }}
                                </td>
                                <td>
                                    <template v-if="child.type === 'file'">
                                        <img  width="50" :src="child.value" alt="">
                                    </template>
                                    <template v-else>
                                        {{ child.value? child.value: '-' }}
                                    </template>
                                </td>
                                <td>
                                    <template v-if="child.type === 'checkbox' || child.type === 'radio'">
                                        <input-tag placeholder="Enter..." v-model="child.new_value" :limit="10"></input-tag>
                                    </template>
                                    <template v-else-if="child.type === 'file'">
                                        <div class="form-group">
                                            <input type="file" :class="['form-control']" @change="onSelectConfigItemFile($event, child)">
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="form-group">
                                            <input v-model="child.new_value" :class="['form-control']">
                                        </div>
                                    </template>
                                </td>

                            </tr>
                        </template>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>
<script>

const img_link ="/images/no_image.jpg";

export default {
    props: ['editModel', 'packages', 'item'],
    data () {
        return {
            form: new Form({
                customer_name: '',
                customer_email: '',
                customer_phone: '',
                package_id: '',
                status: 1,
                expired: '',
                configs: []
            }),
            configs: [],
            formUpload: new Form({
                file: ''
            }),
        }
    },
    created () {
        if (this.editModel) {
            this.loadEdit(this.item);
        }
    },
    methods: {
        loadConfigs () {
            let that = this;
            this.packages.forEach ((item) => {
                if (item.id == that.form.package_id) {
                    let configs = JSON.parse(item.configs)
                    configs.forEach((config) => {
                        config.new_value = config.value;
                        if (config.is_group == 1 && config.items.length > 0) {
                            config.items.forEach((item) => {
                                item.new_value = item.value;
                            })
                        }
                    })

                    that.configs = configs;
                }
            })
        },
        onSelectConfigItemFile (e, item) {
            try {
                let files = e.target.files || e.dataTransfer.files;

                if (!files.length) return;

                if (/\.(jpe?g|png|gif)$/i.test(files[0].name)) {
                    this.formUpload.file = files[0];
                    this.formUpload.submit('post','/admin/common/upload', {
                        transformRequest: [function (data, headers) {
                            return window.objectToFormData.serialize(data)
                        }],
                    })
                        .then(({data}) => {
                            if (data.status) {
                                item.value = data.data.path;
                                item.new_value = data.data.path;
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: data.message
                                });
                            }
                        })
                        .catch((error) => {
                            Toast.fire({
                                icon: 'error',
                                title: 'Lỗi hệ thống'
                            });
                        })
                } else {
                    this.configs[index].value = "";

                    Toast.fire({
                        icon: "error",
                        title: "Image not valid",
                    });
                }
            } catch (error) {}
        },
        addItem () {
            let that = this;
            if (this.itemConfigSelected) {
                let canPush = true;
                this.packageConfigs.forEach((item) => {
                    if (item.id == that.itemConfigSelected) {
                        canPush = false;
                    }
                })
                if (canPush) {
                    this.sourceConfigs.forEach( (item) => {
                        if (item.id == that.itemConfigSelected) {
                            if (item.type === 'checkbox' || item.type === 'radio') {
                                item.new_value = JSON.parse(item.value);
                            } else {
                                item.new_value = item.value;
                            }

                            that.packageConfigs.push(JSON.parse(JSON.stringify(item)));
                        }
                    })
                } else {
                    alert('Config này đã được thêm vào danh sách')
                }
            }
        },

        saveForm () {
            this.form.configs = JSON.stringify(this.configs);
            this.form.submit('post', '/admin/app-customers', {
                transformRequest: [function (data, headers) {
                    return window.objectToFormData.serialize(data)
                }],
            })
                .then(({data}) => {
                    if (data.status) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Tạo mới app khách hàng thành công'
                        });
                        window.location.href = '/admin/app-customers';
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        });
                    }

                }).catch((error) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Lỗi hệ thống'
                });
            })
        },
        loadEdit (item) {
            let that = this;
            this.form.id = item.id;
            this.form.name = item.name;
            this.form.desc = item.desc;
            this.form.status = item.status;
            this.form.price = item.price;
            this.form.source_id = item.source_id;
            this.avatarPreview = item.avatar ? item.avatar : img_link;
            this.loadConfigs();
            let configs = JSON.parse(item.configs);
            let items = [];
            configs.forEach(config => {
                let item = {};
                item.id = config.id;
                item.key = config.key;
                item.type = config.type;
                item.is_edit = config.is_edit;
                item.value = config.old_value;
                item.new_value = config.value;
                item.is_group = config.is_group;
                item.items = [];
                if (config.is_group && config.items.length > 0) {
                    config.items.forEach(ite => {
                        item.items.push({
                            id: ite.id,
                            key: ite.key,
                            type: ite.type,
                            is_edit: ite.is_edit,
                            value: (ite.type == 'checkbox' || ite.type == 'radio') ? ite.old_value : ite.value,
                            new_value: ite.value
                        })
                    })
                }
                items.push(item)
            })
            this.packageConfigs = items;
        },
        edit (id) {
            this.form.configs = JSON.stringify(this.packageConfigs);
            this.form.submit('post', '/admin/packages/'+id, {
                transformRequest: [function (data, headers) {
                    return window.objectToFormData.serialize(data)
                }],
            })
                .then(({data}) => {
                    let status = data.status ? 'success': 'error';

                    Toast.fire({
                        icon: status,
                        title: data.message
                    });

                    if (data.status)
                        window.location.href = '/admin/packages';

                }).catch((error) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Lỗi hệ thống'
                });
            })
        }
    }
}
</script>
