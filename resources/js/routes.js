import VueRouter from "vue-router";
import Bookables from "./bookables/Bookables";
import Bookable from "./bookable/Bookable";
import Review from './review/Review';
import Basket from "./basket/Basket";
import Login from "./auth/Login";
import Register from "./auth/Register";
import store from "./store";

const routes = [
    {
        path: "/",
        component: Bookables,
        name: "home"
    },
    {
        path: "/bookable/:id",
        component: Bookable,
        name: "bookable"
    },
    {
        path: "/review/:id",
        component: Review,
        name: "review"
    },
    {
        path: "/basket",
        component: Basket,
        name: "basket",
        meta: {
            requiresAuth: true
        }
    },
    {
        path: "/auth/login",
        component: Login,
        name: "login"
    },
    {
        path: "/auth/register",
        component: Register,
        name: "register"
    }

];

const router = new VueRouter({
    routes,
    mode: "history"
});

router.beforeEach((to, from, next) => {
    if(to.matched.some(route => route.meta.requiresAuth)) {
        if(store.state.user.id) {
            next();
        }
        else{
            next({path: '/auth/login'});
        }
    }
    next();
})

export default router;
