<?php
    class BancoDeDados {
        // Atributos da Classe
        private $conexao;

        // Método Construtor (conectar)
        public function __construct() {
            $this->conexao = new PDO('mysql:host=localhost;dbname=db_epis;port=3307;charset=utf8mb4', 'root', '');
        }

        // Método Executar Comandos
        public function executarComando($sql, $parametros = []) {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($parametros);
        }

        // Método Iniciar Transação
        public function iniciarTransacao(){
            $this->conexao->beginTransaction();
        }
        // Método Confirmar Transação
        public function confirmarTransacao(){
            $this->conexao->commit();
        }
        // Método voltar Transação
        public function voltarTransacao(){
            $this->conexao->rollBack();
        }

        // Método Consultar
        public function consultar($sql, $parametros = [], $fetchAll = false) {
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($parametros);
            if ($fetchAll) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
        
    }