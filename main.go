package main

import(
	"fmt"
	a2 "github.com/Usama-Fiaz/assignment02"
)

func main() {
	// Create blockchain
	var blockchain a2.Blockchain
	// Create BlockData
	var blockData []a2.Transaction
	// Create transactions # 1
	transaction := a2.NewTransaction("Satoshi", "Vitalik", 10)
	// Add transaction to Block
	blockData = append(blockData, transaction)
	// Create transactions # 1
	transaction = a2.NewTransaction("Satoshi", "Cardano", 23)
	// Add transaction to Block
	blockData = append(blockData, transaction)
	// Add block to blockchain
	blockchain.ChainHead = a2.NewBlock(blockData, nil)
	// Create transactions # 3
	transaction = a2.NewTransaction("Alice", "Bob", 87)
	
	transaction = a2.NewTransaction("Alice", "Bob", 87)
	// Add transaction to Block
	blockData = append(blockData, transaction)
	// Create transactions # 4
	transaction = a2.NewTransaction("Bob", "Alice", 10)
	// Add transaction to Block
	blockData = append(blockData, transaction)
	// Add second block to blockchain
	blockchain.ChainHead = a2.NewBlock(blockData, blockchain.ChainHead)
	// Display blockchain
	a2.ListBlocks(blockchain.ChainHead)
	// Verify cheating
	for blockchain.ChainHead != nil {
	if blockchain.ChainHead.CurrentHash != a2.CalculateHash(blockchain.ChainHead.BlockData, blockchain.ChainHead.Nonce) {
	fmt.Println("Verification failed!")
	return
	}
	blockchain.ChainHead = blockchain.ChainHead.PrevPointer
	}
	fmt.Println("Verification passed!")
	}