#include <stdio.h>

int main(void)
{
	int choice = 0;
	
	printf("\nPick a number between 1 and 10 you may win a prize!");
	scanf("%d", &choice);
	
	if ((choice > 10) || (choice < 1)) {
		choice = 11;	
	}	

	switch(choice) {
		case 7 :
			printf("\n Congratulations!");
			printf("\nYou win the collected works of Amos Gruntfuttock.");
			break;
		case 2 :
			printf("\nYou win the folding thermomter-pen-watch-umbrella.");
			break;
		case 8 :
			printf("\nYou win the lifetime supply of asporin tables.");
			break;
		case 11 :
			printf("\nTry between 1 and 10 . You wasted you guess.");
		default:
			printf("\nSorry , you lose.\n");
			break;	
	}

	return 0;
}
