<?php

function iniciarCarrinho(){
    if(!isset($_SESSION['carrinho'])){
        $_SESSION['carrinho'] = [];
    }
}

function adicionarCarrinho($produto){
    iniciarCarrinho();

    $id = $produto['id'];

    if(isset($_SESSION['carrinho'][$id])){
        $_SESSION['carrinho'][$id]['qtd']++;
    } else {
        $_SESSION['carrinho'][$id] = [
            'id' => $produto['id'],
            'nome' => $produto['nome'],
            'preco' => $produto['preco'],
            'qtd' => 1,
            'img' => $produto['img'] ?? ''  // Mudado de 'imagem' para 'img'
        ];
    }
}

function atualizarQuantidade($id, $quantidade){
    iniciarCarrinho();
    
    if($quantidade <= 0){
        removerItemCarrinho($id);
    } else {
        if(isset($_SESSION['carrinho'][$id])){
            $_SESSION['carrinho'][$id]['qtd'] = $quantidade;
        }
    }
}

function removerItemCarrinho($id){
    iniciarCarrinho();
    
    if(isset($_SESSION['carrinho'][$id])){
        unset($_SESSION['carrinho'][$id]);
    }
}

function totalCarrinho(){
    $total = 0;

    if(isset($_SESSION['carrinho'])){
        foreach($_SESSION['carrinho'] as $item){
            $total += $item['preco'] * $item['qtd'];
        }
    }

    return $total;
}

function contarItensCarrinho(){
    $totalItens = 0;
    
    if(isset($_SESSION['carrinho'])){
        foreach($_SESSION['carrinho'] as $item){
            $totalItens += $item['qtd'];
        }
    }
    
    return $totalItens;
}

function getCarrinho(){
    return $_SESSION['carrinho'] ?? [];
}
?>