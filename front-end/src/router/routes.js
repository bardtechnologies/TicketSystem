const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        name: "Index",
        component: () => import("pages/IndexPage.vue"),
      },
      {
        path: "/ticket",
        name: "TicketIndex",
        component: () => import("pages/ticket/TicketIndexPage.vue"),
      },
      {
        path: "/ticket/:id",
        name: "TicketEditor",
        component: () => import("pages/ticket/TicketPage.vue"),
        props: true,
      },
      {
        path: "/article",
        name: "Articles",
        component: () => import("pages/ArticlePage.vue"),
      },
    ],
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("layouts/LoginLayout.vue"),
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;
