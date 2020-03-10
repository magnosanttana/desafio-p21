# DESAFIO TÉCNICO

## Anoreg (o cliente)
- É uma associação criada para representar os tabeliães de cartórios em qualquer instância ou Tribunal.
- Representa os interesses em comum da classe.
- Também trabalham com cursos e eventos.
- São financiados por contribuições dos cartórios de todo o Brasil.
- O Conselho Nacional de Justiça (CNJ) regulamenta e fiscaliza o trabalho dos cartórios.
- Como eles possuem os cartórios cadastrados, enviam para a Anoreg.

## O problema

-	A Anoreg possui uma base dos cartórios associados em planilha Excel.
-	A planilha é atualizada mensalmente com base em um arquivo XML enviado pelo CNJ. A funcionária da Anoreg abre o arquivo XML em um navegador e atualiza a planilha copiando e colando os dados. 
-	Os campos telefone e e-mail não são informados pelo CNJ, sendo atualizados manualmente na planilha.
-	Existem cartórios que não são enviados pelo CNJ sendo inseridos manualmente na planilha.
-	A Anoreg, periodicamente, envia comunicados para seus associados endereçados aos e-mails cadastrados na planilha. 
-	A Anoreg conta com uma funcionária dedicada exclusivamente para realizar toda essa operação de atualização e envio dos e-mails. 
-	Visando reduzir custos, o presidente da Anoreg ordenou que a funcionária seja demitida, e a operação deverá ser realizada pela secretária que, por sua vez, tem diversas outras ações diárias a fazer e não conseguirá realizar o trabalho da forma como é feito. 


## SOLUÇÃO

Foi desenvolvido uma interface web onde a secretária poderá:

- Cadastrar manualmente, via formulário, um novo cartório
- Ou importar via XML no formado fornecido pelo CNJ. No caso de importação é verificado o atributo "Documento", caso ele não exista na base de dados é inserido e caso já exista é feito uma atualização das informações.
- Independente do método de cadastro (via form ou xml) é possível editar e excluir os registros.
- Na listagem foi implementado na tabela o atributo "Completo?". A ideia é facilitar a identificação visual dos registros que não estão completos (não possuem TELEFONE ou EMAIL cadastrado, dados ausentes no XML).
- Foi implementado um filtro também, assim a secretária pode filtrar, por exemplo, apenas os cartórios de determinada cidade.
- A partir do resultado do filtro é possível exportá-lo para uma planilha excel, no mesmo formato que é trabalhado atualmente.
- Também a partir do resultado do filtro é possível agendar o envio de uma mensagem que será enviado por email à todos os registros daquele resultado filtrado (todos os registros que possuam email, obviamente).
- É possível fazer acompanhamento de quais emails já foram enviados e quais ainda estão aguardando ou falharam.
 
## EXECUÇÂO

Siga os seguintes passos:

- Após clone do projeto, instalar as dependencias via composer
`composer install`
- Duplicar o arquivo, presente na raiz, `.env.example` para `.env` e inserir as informações de conexão com o banco de dados e de autenticação SMTP
- Importar no banco o arquivo `p21_desafio.sql`, presente na raiz, para criar as tabelas necessárias
- Configurar o servidor web para rodar na pasta `./public/` do projeto
- Configurar um 'cronjob' para executar a url `http://{endereco.do.projeto}/disparar-envio` é através desta rota que os envios são feitos

## VISUALIZAÇÃO DO PROJETO EM EXECUÇÃO:
 * [http://desafiop21.magnosanttana.com.br/](http://desafiop21.magnosanttana.com.br/) 