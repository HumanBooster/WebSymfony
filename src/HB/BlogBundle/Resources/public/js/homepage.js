/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


        $(function() {



            $("article.article").hover(
                function(){
                    $(this).addClass('article_actif', 500);
                },
                function(){
                    $(this).removeClass('article_actif', 500);
                }
             );

        });