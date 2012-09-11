#include <stdio.h>

int change (int number);

int main(void)
{
	int number = 10;
	int result = 0;
	result = change(number);
	printf("\nIn main, result = %d\t number = %d", result, number);
	
	return 0;	
}

int change (int number)
{
	number = 2 * number;
	printf("\nIn function change, number = %d\n", number);
	
	return number;	
}
