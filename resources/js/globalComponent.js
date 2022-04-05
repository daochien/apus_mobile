import Vue from 'vue';

Vue.component(
    "admin-source-index",
    require("./components/admin/source/Index").default
);

Vue.component(
    "admin-source-form",
    require("./components/admin/source/Form").default
);

Vue.component(
    "admin-source-config-index",
    require("./components/admin/source_config/Index").default
);

Vue.component(
    "admin-source-config-form",
    require("./components/admin/source_config/Form").default
);

Vue.component(
    "admin-package-form",
    require("./components/admin/package/Form").default
);

Vue.component(
    "admin-package-index",
    require("./components/admin/package/Index").default
);

Vue.component(
    "admin-app-customer-form",
    require("./components/admin/app_customer/Form").default
);

