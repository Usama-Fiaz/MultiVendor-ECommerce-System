package main

import (
	"crypto/sha256"
	"fmt"
	"strconv"
	"time"
)

type Transaction struct {
	TransactionID string
	Sender        string
	Receiver      string
	Amount        int
}

type Block struct {
	Nonce       int
	BlockData   []Transaction
	PrevPointer *Block
	PrevHash    string
	CurrentHash string
}

type Blockchain struct {
	ChainHead *Block
}

func GenerateNonce(blockData []Transaction) int {
	return 10
}

func CalculateHash(blockData []Transaction, nonce int) string {
	dataString := ""
	for i := 0; i < len(blockData); i++ {
		dataString += (blockData[i].TransactionID + blockData[i].Sender +
			blockData[i].Receiver + strconv.Itoa(blockData[i].Amount)) + strconv.Itoa(nonce)
	}
	return fmt.Sprintf("%x", sha256.Sum256([]byte(dataString)))
}

func NewBlock(blockData []Transaction, chainHead *Block) *Block {
	B := new(Block)
	if chainHead == nil{
		// int nonce = GenerateNonce(blockData)
		B.Nonce = 10
		B.CurrentHash = CalculateHash(blockData,B.Nonce)
		B.PrevHash = "nil"
		B.BlockData = blockData[:]
		B.PrevPointer = nil

	} else {
		B.Nonce = 20
		B.CurrentHash = CalculateHash(blockData,B.Nonce)
		B.PrevHash = chainHead.CurrentHash
		B.BlockData = blockData[:]
		B.PrevPointer = chainHead
	}
	return B
}

func ListBlocks(chainHead *Block) {
	// First Take a Temp Variable for Chain Header so that it may not be Misplaced.
	Temp_chainHead := new(Block)
	Temp_chainHead = chainHead

	fmt.Println("\n\n\t\t\t\t\t\t\t🟥 Listing All Blocks\n")
	i := 0
	for Temp_chainHead != nil {
		i++
		fmt.Println("❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌ Block No : ",i," ❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌❌")
		fmt.Println("✅ Nonce : ",Temp_chainHead.Nonce)
		fmt.Println("✅ CurrentHash : ",Temp_chainHead.CurrentHash)
		fmt.Println("✅ PrevPointer : ",Temp_chainHead.PrevPointer)
		fmt.Println("✅ PrevHash : ",Temp_chainHead.PrevHash)
		fmt.Println("✅ Block/Transaction Data : ")
		DisplayTransactions(Temp_chainHead.BlockData)

		Temp_chainHead=Temp_chainHead.PrevPointer
	}
}

func DisplayTransactions(blockData []Transaction) {
	var i=0
	for i=0;i<len(blockData);i++ {
		fmt.Println("\t\t\t====================================== (",i+1,") ======================================")
		fmt.Println("\t\t\tTransactionID : ",blockData[i].TransactionID)
		fmt.Println("\t\t\tSender : ",blockData[i].Sender)
		fmt.Println("\t\t\tReceiver : ",blockData[i].Receiver)
		fmt.Println("\t\t\tAmount : ",blockData[i].Amount)
	}
	fmt.Println("\t\t\t===================================================================================\n")
}

func NewTransaction(sender string, receiver string, amount int) Transaction {
	T := new(Transaction)

	t := time.Now()
	str_amount:=strconv.Itoa(amount)

	TransactionId_string:=fmt.Sprintf("%x", sha256.Sum256([]byte(t.String()+sender+receiver+str_amount)))

	T.TransactionID = TransactionId_string
	T.Sender = sender
	T.Receiver = receiver
	T.Amount = amount

	return *T
}

func main() {
	var blockchain Blockchain

	// Create BlockData
	var blockData []Transaction

	// Create transactions # 1
	transaction := NewTransaction("Satoshi", "Vitalik", 10)
	// Add transaction to Block
	blockData = append(blockData,transaction)

	// Create transactions # 2
	transaction = NewTransaction("Satoshi", "Cardano", 23)
	// Add transaction to Block
	blockData = append(blockData,transaction)
	
	// Add block to blockchain
	blockchain.ChainHead = NewBlock(blockData, nil)

	// Create transactions # 3
	transaction = NewTransaction("Alice", "Bob", 87)
	// Add transaction to Block
	blockData = append(blockData, transaction)

	// Create transactions # 4
	transaction = NewTransaction("Bob", "Alice", 10)
	// Add transaction to Block
	blockData = append(blockData, transaction)

	// Add second block to blockchain
	blockchain.ChainHead = NewBlock(blockData, blockchain.ChainHead)
	// Display blockchain
	ListBlocks(blockchain.ChainHead)

	// Verify cheating
	for blockchain.ChainHead != nil {
		if blockchain.ChainHead.CurrentHash != CalculateHash(blockchain.ChainHead.BlockData, blockchain.ChainHead.Nonce) {
			fmt.Println("Verification failed!")
			return
		}
		blockchain.ChainHead = blockchain.ChainHead.PrevPointer
	}
	fmt.Println("Verification passed!")
}