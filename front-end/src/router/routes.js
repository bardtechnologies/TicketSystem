
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '/login', component: () => import('pages/LoginPage.vue') },
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: '/ticket', component: () => import('pages/TicketPage.vue') },
      { path: '/article/:id', component: () => import('pages/ArticlePage.vue'), props:true },
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
