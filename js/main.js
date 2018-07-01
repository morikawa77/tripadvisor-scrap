new Vue({
    el: "#app",

    data:{
        nome: "",
        telefone: "",
        reviews: "",
        imagens: "",
        status: "",
        url: "",
        seen: true
    },

    methods: {
        mounted()
        {
            loadProgressBar();
        },
        getRestaurant()
        {
            loadProgressBar();
            console.log(this.url);

            if(this.url.startsWith("https://www.tripadvisor.com.br/Restaurant")){
                axios.post('tripadvisor-scrapper.php', this.url)
                    .then(response => {
                        if (response.data.status != null) {
                            this.status = response.data.status;
                        } else {
                            this.nome = response.data.nome;
                            this.telefone = 'Telefone: ' + response.data.telefone;
                            this.reviews = response.data.reviews + ' avaliações';
                            this.imagens = response.data.imagens;
                        }
                    });
            } else {
                this.clearRestaurant();
                this.status = "URL da pesquisa inválida, verifique os dados e tente novamente.";
            }


        },
        clearRestaurant: function () {
            this.nome = "",
                this.telefone = "",
                this.reviews = "",
                this.imagens = "",
                this.status = "",
                this.url = ""
        },
        fillRestaurant: function () {
            this.url = "https://www.tripadvisor.com.br/Restaurant_Review-g303628-d4201091-Reviews-Mirai_Japanese_Food-Sao_Jose_Do_Rio_Preto_State_of_Sao_Paulo.html";
        }
    }
});