import Home from './components/Home.vue';
import Login from './components/auth/Login.vue';
import CustomersMain from './components/customers/Main.vue';
import CustomersList from './components/customers/List.vue';
import NewCustomer from './components/customers/New.vue';
import Customer from './components/customers/View.vue';
import EditCustomer from './components/customers/Edit.vue';

import TransactionsMain from './components/transactions/Main.vue';
import TransactionsList from './components/transactions/List.vue';


export const routes =[
    {
        path:'/',
        component: Home,
        meta: {
            requiresAuth: true
        }
    }
    ,{
        path: '/login',
        component: Login
    }
    ,{
        path: '/customers',
        component: CustomersMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: CustomersList
            }
            , {
                path: 'new',
                component: NewCustomer
            }
            , {
                path: ':id',
                component: Customer
            }
            , {
                path: 'edit/:id',
                component: EditCustomer
            }
            // , {
            //     path: ':id/transactions',
            //     component: Customer
            // }
        ]
    }
    ,{
        path: '/transactions',
        component: TransactionsMain,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: '/',
                component: TransactionsList
            }

        ]
    }
];