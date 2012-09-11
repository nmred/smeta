#include <stdio.h>

int change(int *pnumber);

int main(void)
{
	int number = 10;
	int *pnumber = &number;
	int result = 0;
	
	result = change(pnumber);
	printf("\n In main , result = %d\t number = %d", result, number);
	
	return 0;	
}

int change(int *pnumber)
{
	*pnumber *= 2;
	printf("\nIn function change, *pnumber = %d\n", *pnumber);
	return *pnumber;	
}
