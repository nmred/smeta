#include <stdio.h>

int main(void)
{
	char board[3][3] = {
		{'1', '2', '3'},
		{'4', '5', '6'},
		{'7', '8', '9'}	
	};	

	printf("address of board         : %p\n", board);
	printf("address of board[0][0]   : %p\n", &board[0][0]);
	printf("but what is in board[0]  : %p\n", board[0]);

	printf("----------------------------");
	printf("\n%p\n", &board);
	printf("%p\n", &board[0]);
	printf("%p\n", board[0][0]);
	return 0;
}
