export const Ziggy = {
    url: window.location.origin,
    port: window.location.port ? window.location.port : null,
    defaults: {},
    routes: {
        'login': {
            'uri': 'login',
            'methods': ['GET', 'HEAD']
        },
        'register': {
            'uri': 'register',
            'methods': ['GET', 'HEAD']
        },
        'dashboard': {
            'uri': 'dashboard',
            'methods': ['GET', 'HEAD']
        },
        'chatbots.index': {
            'uri': 'chatbots',
            'methods': ['GET', 'HEAD']
        },
        'chatbots.create': {
            'uri': 'chatbots/create',
            'methods': ['GET', 'HEAD']
        },
        'chatbots.store': {
            'uri': 'chatbots',
            'methods': ['POST']
        },
        'chatbots.show': {
            'uri': 'chatbots/{chatbot}',
            'methods': ['GET', 'HEAD']
        },
        'chatbots.edit': {
            'uri': 'chatbots/{chatbot}/edit',
            'methods': ['GET', 'HEAD']
        },
        'chatbots.update': {
            'uri': 'chatbots/{chatbot}',
            'methods': ['PUT', 'PATCH']
        },
        'chatbots.destroy': {
            'uri': 'chatbots/{chatbot}',
            'methods': ['DELETE']
        },
        'widget.show': {
            'uri': 'widget/{uuid}',
            'methods': ['GET', 'HEAD']
        },
        'widget.send': {
            'uri': 'widget/{uuid}/send',
            'methods': ['POST']
        },
        'widget.info': {
            'uri': 'widget/{uuid}/info',
            'methods': ['GET', 'HEAD']
        }
    }
};
